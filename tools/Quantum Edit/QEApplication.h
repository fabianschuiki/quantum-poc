//
//  QEApplication.h
//  Quantum Edit
//
//  Created by Fabian Schuiki on 03.02.13.
//  Copyright 2013 Axamblis. All rights reserved.
//

#import <Cocoa/Cocoa.h>

@class QEFrame;


@interface QEApplication : NSApplication
{
	IBOutlet NSTextField *quantumPath;
	NSFileHandle *server;
}

- (IBAction)openQuantum:(id)sender;

@end