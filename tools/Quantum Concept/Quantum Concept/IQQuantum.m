//
//  IQQuantum.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQQuantum.h"

@implementation IQQuantum

+ (id)quantum
{
	return [[[self alloc] init] autorelease];
}

- (id)init
{
	self = [super init];
	if (self) {
		self.ID = 0;
	}
	return self;
}

- (NSString *)description
{
	return [NSString stringWithFormat:@"%@{%lu, %@}", [super description], self.ID, self.name];
}

- (void)commit
{
	[[NSNotificationCenter defaultCenter] postNotificationName:@"IQQuantumCommitted" object:self];
	NSLog(@"Committing %@", self);
}

@end
