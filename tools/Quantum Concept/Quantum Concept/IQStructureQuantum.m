//
//  IQStructureQuantum.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQStructureQuantum.h"

@implementation IQStructureQuantum

- (id)init
{
	self = [super init];
	if (self) {
		fields = [[NSMutableDictionary dictionary] retain];
	}
	return self;
}

- (void)dealloc
{
	[fields release];
	return [super dealloc];
}

- (NSDictionary *)fields
{
	return fields;
}

@end
