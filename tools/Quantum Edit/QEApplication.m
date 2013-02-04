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


@implementation QEApplication

- (IBAction)openQuantum:(id)sender
{
	NSLog(@"Opening Quantum %@", [quantumPath stringValue]);
}

- (void)finishLaunching
{
	[super finishLaunching];
	
	//Connect to the quantum server.
	NSString *socketPath = @"/tmp/quantum-frames.sock";
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
	[server readInBackgroundAndNotify];
	[serverBuffer appendData:[[notification userInfo] objectForKey:NSFileHandleNotificationDataItem]];
	
	//Decode the frame we received.
	NSUInteger consumed = 0;
	QEFrame *frame = [QEFrame unserialize:serverBuffer consumed:&consumed];
	[serverBuffer replaceBytesInRange:NSMakeRange(0, consumed) withBytes:NULL length:0];
	
	NSLog(@"Received frame %@", frame);
}

@end
