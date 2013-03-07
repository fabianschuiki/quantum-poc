//
//  IQStringQuantum.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQStringQuantum.h"

@implementation IQStringQuantum

@synthesize string;

- (id)init
{
	self = [super init];
	if (self) {
		string = [[NSMutableString string] retain];
	}
	return self;
}

- (NSString *)type { return @"string"; }

@end
