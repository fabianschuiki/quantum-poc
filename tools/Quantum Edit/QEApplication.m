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
	
	//Send some random stuff.
	QEFrame *f = [QEFrame frameWithType:1 data:[[QEFrame frameWithType:1 data:[@"Hello World" dataUsingEncoding:NSUTF8StringEncoding]] serialize]];
	[server writeData:[f serialize]];
}

- (void)dealloc
{
	[server release];
	[super dealloc];
}

@end
