//
//  IQCaster.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQCaster.h"
#import "IQQuantum.h"

@implementation IQCaster

+ (id)caster
{
	return [[[self alloc] init] autorelease];
}

- (void)cast:(IQQuantum *)from to:(IQQuantum *)to
{
	if ([from.type isEqualToString:self.input]) {
		[self forwardCast:from to:to];
	} else {
		[self backwardCast:from to:to];
	}
}

- (void)forwardCast:(IQQuantum *)input to:(IQQuantum *)output
{
}

- (void)backwardCast:(IQQuantum *)output to:(IQQuantum *)input
{
}

@end
