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
#import "IQRelation.h"

@implementation IQServer

@synthesize repository = _repo;

- (id)init
{
	self = [super init];
	if (self) {
		_repo = [[IQRepository alloc] init];
		casters = [[NSMutableSet set] retain];
		relations = [[NSMutableSet set] retain];
		
		// Initialize the default casters.
		[casters addObject:[IQFileDataCaster caster]];
		[casters addObject:[IQDataStringCaster caster]];
		
		NSLog(@"initialized casters %@", casters);
		
		// Add some quantum.
		IQStructureQuantum *iq = [[IQStructureQuantum alloc] init];
		iq.type = @"file";
		iq.name = @"world.txt";
		[_repo addQuantum:iq];
		
		// Register for whenever a quantum commits.
		[[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(quantumCommitNotification:) name:@"IQQuantumCommitted" object:nil];
	}
	return self;
}

- (NSSet *)casters
{
	return casters;
}

- (void)castQuantum:(IQQuantum *)quantum with:(IQCaster *)caster
{
	// Check whether the cast already exists, in which case we do nothing.
	for (IQRelation *rel in relations) {
		if (rel.parent == quantum && rel.caster == caster)
			return;
	}
	
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
	
	// Keep track of the relation between the two quanta.
	IQRelation *rel = [IQRelation relation];
	rel.parent = quantum;
	rel.wrapper = dst;
	rel.caster = caster;
	[relations addObject:rel];
	
	// Keep the names in sync.
	[dst bind:@"name" toObject:quantum withKeyPath:@"name" options:nil];
}

- (void)quantumCommitNotification:(NSNotification *)notification
{
	IQQuantum *quantum = [notification object];
	[[NSNotificationCenter defaultCenter] postNotificationName:@"IQQuantumChanged" object:quantum];
	
	// Look for initial relations that are affected.
	NSMutableSet *downstreamStack = [NSMutableSet set];
	NSMutableSet *upstreamStack = [NSMutableSet set];
	for (IQRelation *rel in relations) {
		if (rel.parent == quantum) [downstreamStack addObject:rel];
		if (rel.wrapper == quantum) [upstreamStack addObject:rel];
	}
	
	// Work through the relation stack.
	NSMutableSet *updatedRelations = [NSMutableSet set];
	while ([downstreamStack count] || [upstreamStack count]) {
		IQRelation *rel = nil;
		IQQuantum *affectedQuantum = nil;
		if ([downstreamStack count]) {
			rel = [downstreamStack anyObject];
			[downstreamStack removeObject:rel];
			[updatedRelations addObject:rel];
			[rel.caster cast:rel.parent to:rel.wrapper];
			affectedQuantum = rel.wrapper;
		} else {
			rel = [upstreamStack anyObject];
			[upstreamStack removeObject:rel];
			[updatedRelations addObject:rel];
			[rel.caster cast:rel.wrapper to:rel.parent];
			affectedQuantum = rel.parent;
		}
		
		// Inform observers about the changes to the affected quantum.
		if (affectedQuantum)
			[[NSNotificationCenter defaultCenter] postNotificationName:@"IQQuantumChanged" object:affectedQuantum];
		
		// Look for relations concerning the affected quantum and add them to the stack.
		for (IQRelation *r in relations) {
			if ([updatedRelations containsObject:r]) continue;
			if (r.parent == affectedQuantum) [downstreamStack addObject:r];
			if (r.wrapper == affectedQuantum) [upstreamStack addObject:r];
		}
	}
}

@end
