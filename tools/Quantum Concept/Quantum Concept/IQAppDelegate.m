//
//  IQAppDelegate.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQAppDelegate.h"
#import "IQServer.h"
#import "IQServerInspector.h"

@implementation IQAppDelegate

- (void)dealloc
{
    [super dealloc];
}

- (void)applicationDidFinishLaunching:(NSNotification *)aNotification
{
	// Insert code here to initialize your application
	[self.serverInspector bind:@"repository" toObject:self.server withKeyPath:@"repository" options:nil];
}

@end
