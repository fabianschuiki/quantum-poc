//
//  IQServer.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import <Foundation/Foundation.h>

@class IQRepository;
@class IQQuantum;
@class IQCaster;


@interface IQServer : NSObject
{
	NSMutableSet *casters;
}

@property (readonly) IQRepository *repository;
@property (readonly) NSSet *casters;

- (id)init;

- (void)castQuantum:(IQQuantum *)quantum with:(IQCaster *)caster;

@end
