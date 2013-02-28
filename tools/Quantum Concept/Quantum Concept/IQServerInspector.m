//
//  IQServerInspector.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQServerInspector.h"
#import "IQRepository.h"
#import "IQQuantum.h"
#import "IQStructureQuantum.h"

@implementation IQServerInspector

@synthesize outlineView, repository;


- (id)init
{
	self = [super init];
	if (self) {
		[[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(quantumChanged:) name:@"IQQuantumChanged" object:nil];
	}
	return self;
}

- (void)setRepository:(IQRepository *)repo
{
	if (repo != repository) {
		[repo retain];
		[repository autorelease];
		repository = repo;
		[outlineView reloadData];
	}
}

- (void)quantumChanged:(NSNotification *)notification
{
	IQQuantum *quantum = [notification object];
	if (quantum) {
		[outlineView reloadItem:quantum];
	} else {
		[outlineView reloadItem:nil];
	}
}

- (id)outlineView:(NSOutlineView *)outlineView child:(NSInteger)index ofItem:(id)item
{
	// The root level shows all items in the repository.
	if (!item) {
		return [repository.quanta objectAtIndex:index];
	}
	
	// Sublevels show the children of the respective item.
	else {
		if ([item isKindOfClass:[IQStructureQuantum class]]) {
			return [[[item fields] allValues] objectAtIndex:index];
		}
		return nil;
	}
}

- (BOOL)outlineView:(NSOutlineView *)outlineView isItemExpandable:(id)item
{
	return [item isKindOfClass:[IQStructureQuantum class]] && ([[item fields] count] > 0);
}

- (NSInteger)outlineView:(NSOutlineView *)outlineView numberOfChildrenOfItem:(id)item
{
	// The root level shows all items in the repository.
	if (!item) {
		return [repository.quanta count];
	}
	
	// Sublevels show the children of the respective item.
	else {
		if ([item isKindOfClass:[IQStructureQuantum class]]) {
			return [[item fields] count];
		}
		return 0;
	}
}

- (id)outlineView:(NSOutlineView *)outlineView objectValueForTableColumn:(NSTableColumn *)tableColumn byItem:(id)item
{
	NSString *ident = [tableColumn identifier];
	IQQuantum *iq = item;
	
	if ([ident isEqualToString:@"id"]) {
		return [NSString stringWithFormat:@"%lu", iq.ID];
	}
	else if ([ident isEqualToString:@"name"]) {
		return iq.name;
	}
	else if ([ident isEqualToString:@"details"]) {
		if ([iq isKindOfClass:[IQStructureQuantum class]]) {
			return [NSString stringWithFormat:@"%@", iq.type];
		}
		return [item description];
	}
	return nil;
}

@end
