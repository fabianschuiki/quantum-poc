//
//  IQTreeBrowser.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 03.03.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import <Foundation/Foundation.h>

@class IQServer;

@interface IQTreeBrowser : NSObject

@property (assign) IBOutlet NSBrowser *browser;
@property (assign) IBOutlet IQServer *server;

@end
