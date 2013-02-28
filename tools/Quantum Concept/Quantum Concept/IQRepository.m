//
//  IQRepository.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQRepository.h"
#import "IQQuantum.h"

@implementation IQRepository

- (id)init
{
	self = [super init];
	if (self) {
		quanta = [[NSMutableArray array] retain];
		indices = [[NSMutableIndexSet indexSet] retain];
	}
	return self;
}

- (void)dealloc
{
	[quanta release];
	[indices release];
	[super dealloc];
}

- (NSArray *)quanta
{
	return quanta;
}

- (void)addQuantum:(IQQuantum *)iq
{
	if (![quanta containsObject:iq]) {
		NSAssert(iq.ID == 0, @"Trying to add IQ %@ with non-null ID %lu to %@", iq, iq.ID, self);
		NSUInteger index = [indices lastIndex];
		if (index == NSNotFound) {
			index = 1;
		} else {
			index++;
		}
		[indices addIndex:index];
		iq.ID = index;
		[quanta addObject:iq];
		
		[[NSNotificationCenter defaultCenter] postNotificationName:@"IQQuantumChanged" object:nil];
	}
}

- (void)removeQuantum:(IQQuantum *)iq
{
	if ([quanta containsObject:iq]) {
		[indices removeIndex:iq.ID];
		iq.ID = 0;
		[quanta removeObject:iq];
		[[NSNotificationCenter defaultCenter] postNotificationName:@"IQQuantumChanged" object:nil];
	}
}

@end
