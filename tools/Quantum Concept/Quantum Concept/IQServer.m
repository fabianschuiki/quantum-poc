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
#import "IQDataQuantum.h"
#import "IQStringQuantum.h"
#import "IQFileDataCaster.h"
#import "IQDataStringCaster.h"

@implementation IQServer

@synthesize repository = _repo;

- (id)init
{
	self = [super init];
	if (self) {
		_repo = [[IQRepository alloc] init];
		casters = [[NSMutableSet set] retain];
		
		// Initialize the default casters.
		[casters addObject:[IQFileDataCaster caster]];
		[casters addObject:[IQDataStringCaster caster]];
		
		NSLog(@"initialized casters %@", casters);
		
		// Add some quantum.
		IQStructureQuantum *iq = [[IQStructureQuantum alloc] init];
		iq.type = @"file";
		iq.name = @"world.txt";
		[_repo addQuantum:iq];
	}
	return self;
}

- (NSSet *)casters
{
	return casters;
}

- (void)castQuantum:(IQQuantum *)quantum with:(IQCaster *)caster
{
	// Find out what the destination type is.
	NSString *dstType = caster.output;
	if ([quantum.type isEqualToString:caster.output])
		dstType = caster.input;
	
	// Create a new quantum to hold the casted information.
	IQQuantum *dst = nil;
	if ([dstType isEqualToString:@"data"])
		dst = [IQDataQuantum quantum];
	else if ([dstType isEqualToString:@"string"])
		dst = [IQStringQuantum quantum];
	else {
		dst = [IQStructureQuantum quantum];
		dst.type = dstType;
	}
	
	// Perform the initial conversion.
	[caster cast:quantum to:dst];
	
	// Store the output in the repository.
	[_repo addQuantum:dst];
}

@end
