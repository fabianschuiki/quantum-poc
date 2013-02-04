//
//  QEApplication.m
//  Quantum Edit
//
//  Created by Fabian Schuiki on 03.02.13.
//  Copyright 2013 Axamblis. All rights reserved.
//

#import "QEApplication.h"
#include <sys/socket.h>
#include <sys/un.h>
#import "QEFrame.h"
#import "QEEditor.h"


enum {
	kStringFrameType = 1,
	kIntegerFrameType = 2,
	
	kRequestQuantumFrameType = 200,
	kRequestQuantumResponseFrameType = 201
};


@implementation QEApplication

- (IBAction)openQuantum:(id)sender
{
	//NSLog(@"Opening Quantum %@", [quantumPath stringValue]);
	//[server writeData:[[QEFrame frameWithType:2 data:[[quantumPath stringValue] dataUsingEncoding:NSUTF8StringEncoding]] serialize]];
	
	//Create a new editor for this quantum.
	[editors addObject:[[[QEEditor alloc] initWithPath:[quantumPath stringValue]] autorelease]];
}

- (void)finishLaunching
{
	editors = [[NSMutableSet set] retain];
	requestID = 0;
	
	[super finishLaunching];
	
	//Connect to the quantum server.
	NSString *socketPath = @"/tmp/quantum.sock";
	NSLog(@"Connecting to %@", socketPath);
	int s = socket(AF_UNIX, SOCK_STREAM, 0);
	if (!s) {
		[NSException raise:NSInternalInconsistencyException format:@"Unable to create socket."];
	}
	struct sockaddr_un addr;
	memset(&addr, 0, sizeof(addr));
	addr.sun_family = AF_UNIX;
	strcpy(addr.sun_path, [socketPath UTF8String]);
	if (connect(s, (struct sockaddr*)&addr, SUN_LEN(&addr)) != 0) {
		NSRunAlertPanel(@"Quantum Server not Running", @"Unable to connect to %@. %s.", @"Quit", nil, nil, socketPath, strerror(errno));
		[self terminate:nil];
		return;
	}
	
	//Wrap the socket in a file handle.
	server = [[NSFileHandle alloc] initWithFileDescriptor:s closeOnDealloc:YES];
	serverBuffer = [[NSMutableData data] retain];
	[[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(receivedFromServer:) name:NSFileHandleReadCompletionNotification object:nil];
	[server readInBackgroundAndNotify];
}

- (void)dealloc
{
	[server release];
	[serverBuffer release];
	[super dealloc];
}

- (void)receivedFromServer:(NSNotification *)notification
{
	NSData *data = [[notification userInfo] objectForKey:NSFileHandleNotificationDataItem];
	if ([data length])
		[server readInBackgroundAndNotify];
	[serverBuffer appendData:data];
	
	//Decode the frame we received.
	NSUInteger consumed = 0;
	QEFrame *frame;
	while ((frame = [QEFrame unserialize:serverBuffer consumed:&consumed forgiving:YES])) {
		[serverBuffer replaceBytesInRange:NSMakeRange(0, consumed) withBytes:NULL length:0];
		NSLog(@"Received frame %@", frame);
		
		//Handle the received frame.
		switch (frame.type) {
			case kRequestQuantumResponseFrameType: {
				NSLog(@"Server sent quantum request response %@", frame);
			} break;
			case 255: {
				NSRunAlertPanel(@"Server Error", @"The Quantum Server returned an error: %@.", @"OK", nil, nil, [[[NSString alloc] initWithData:frame.data encoding:NSUTF8StringEncoding] autorelease]);
			} break;
			default: {
				NSLog(@"Received frame %@ is not supported.", frame);
			} break;
		}
	}
	
	if ([data length] == 0) {
		NSRunAlertPanel(@"Server Connection Lost", @"The Quantum Server disconnected. Quantum Edit cannot function without a running server.", @"Quit", nil, nil);
		[self terminate:nil];
	}
}

- (NSInteger)requestQuantumWithPath:(NSString *)path
{
	requestID++;
	NSMutableData *data = [NSMutableData dataWithData:[[self encodeAsFrame:[NSNumber numberWithInteger:requestID]] serialize]];
	[data appendData:[[self encodeAsFrame:path] serialize]];
	QEFrame *request = [QEFrame frameWithType:kRequestQuantumFrameType data:data];
	[server writeData:[request serialize]];
	return requestID;
}

- (QEFrame *)encodeAsFrame:(id)object
{
	if ([object isKindOfClass:[NSString class]]) {
		return [QEFrame frameWithType:kStringFrameType data:[object dataUsingEncoding:NSUTF8StringEncoding]];
	}
	if ([object isKindOfClass:[NSNumber class]]) {
		int32_t i = [object intValue];
		return [QEFrame frameWithType:kIntegerFrameType data:[NSData dataWithBytes:&i length:4]];
	}
	return nil;
}

- (id)decodeFromFrame:(QEFrame *)frame
{
	switch (frame.type) {
		case 1: {
			return [[[NSString alloc] initWithData:frame.data encoding:NSUTF8StringEncoding] autorelease];
		} break;
		case 2: {
			int32_t i;
			[frame.data getBytes:&i length:4];
			return [NSNumber numberWithInt:i];
		} break;
	}
	NSLog(@"*** Unable to decode frame %@", frame);
	return nil;
}

@end
