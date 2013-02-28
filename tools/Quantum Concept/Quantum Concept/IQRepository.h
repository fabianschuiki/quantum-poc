//
//  IQRepository.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import <Foundation/Foundation.h>

@class IQQuantum;

@interface IQRepository : NSObject
{
	NSMutableArray *quanta;
	NSMutableIndexSet *indices;
}

@property (readonly) NSArray *quanta;

- (id)init;

- (void)addQuantum:(IQQuantum *)iq;
- (void)removeQuantum:(IQQuantum *)iq;

@end
