//
//  IQArrayQuantum.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 03.03.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQArrayQuantum.h"

@implementation IQArrayQuantum

- (id)init
{
	self = [super init];
	if (self) {
		quanta = [[NSMutableArray array] retain];
		self.quantaType = nil;
	}
	return self;
}

- (NSArray *)quanta
{
	return quanta;
}

- (void)addQuantum:(IQQuantum *)q
{
	if (![quanta containsObject:q]) {
		[quanta addObject:q];
	}
}

- (void)removeQuantum:(IQQuantum *)q
{
	[quanta removeObject:q];
}

@end
