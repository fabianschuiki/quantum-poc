//
//  IQDataQuantum.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQDataQuantum.h"

@implementation IQDataQuantum

@synthesize data;

- (id)init
{
	self = [super init];
	if (self) {
		data = [[NSMutableData data] retain];
	}
	return self;
}

- (NSString *)type { return @"data"; }

@end
