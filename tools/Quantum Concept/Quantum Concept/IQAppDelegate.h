//
//  IQAppDelegate.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import <Cocoa/Cocoa.h>

@class IQServer, IQServerInspector, IQQuantum, IQEditor;


@interface IQAppDelegate : NSObject <NSApplicationDelegate>
{
	NSMutableDictionary *editorTypeMap;
	NSMutableSet *editors;
}

@property (assign) IBOutlet NSWindow *window;
@property (retain) IBOutlet IQServer *server;
@property (retain) IBOutlet IQServerInspector *serverInspector;

- (BOOL)canEditQuantum:(IQQuantum *)iq;
- (void)editQuantum:(IQQuantum *)iq;
- (void)editorClosed:(IQEditor *)editor;

@end
