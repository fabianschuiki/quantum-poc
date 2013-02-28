//
//  IQServerInspector.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import <Foundation/Foundation.h>

@class IQServer;

@interface IQServerInspector : NSObject

@property (assign) IBOutlet NSOutlineView *outlineView;
@property (assign) IBOutlet IQServer *server;

@property (assign) IBOutlet NSWindow *castPicker;
@property (assign) IBOutlet NSPopUpButton *castPopup;

- (id)init;

- (IBAction)castQuantum:(id)sender;
- (IBAction)editQuantum:(id)sender;

- (IBAction)acceptCast:(id)sender;
- (IBAction)cancelCast:(id)sender;

@end
