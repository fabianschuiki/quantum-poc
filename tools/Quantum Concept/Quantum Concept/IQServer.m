//
//  IQServer.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQServer.h"
#import "IQRepository.h"
#import "IQStructureQuantum.h"

@implementation IQServer

@synthesize repository = _repo;

- (id)init
{
	self = [super init];
	if (self) {
		_repo = [[IQRepository alloc] init];
		
		// Add some quantum.
		IQStructureQuantum *iq = [[IQStructureQuantum alloc] init];
		iq.type = @"file";
		iq.name = @"world.txt";
		[_repo addQuantum:iq];
	}
	return self;
}

@end
