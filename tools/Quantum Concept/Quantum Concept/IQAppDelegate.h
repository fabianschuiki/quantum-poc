//
//  IQAppDelegate.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import <Cocoa/Cocoa.h>

@class IQServer, IQServerInspector;


@interface IQAppDelegate : NSObject <NSApplicationDelegate>

@property (assign) IBOutlet NSWindow *window;
@property (retain) IBOutlet IQServer *server;
@property (retain) IBOutlet IQServerInspector *serverInspector;

@end
