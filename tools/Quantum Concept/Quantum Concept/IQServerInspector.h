//
//  IQServerInspector.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import <Foundation/Foundation.h>

@class IQRepository;

@interface IQServerInspector : NSObject

@property (assign) IBOutlet NSOutlineView *outlineView;
@property (nonatomic, retain) IQRepository *repository;

- (id)init;

@end
