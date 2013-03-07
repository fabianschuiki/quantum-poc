//
//  IQTreeBrowser.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 03.03.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQTreeBrowser.h"
#import "IQStructureQuantum.h"
#import "IQStringQuantum.h"
#import "IQServer.h"
#import "IQRepository.h"
#import "IQArrayQuantum.h"

@implementation IQTreeBrowser

@synthesize browser, server;

- (id)rootItemForBrowser:(NSBrowser *)browser
{
	for (IQQuantum *q in server.repository.quanta) {
		if ([q.name isEqualToString:@"filesystem"]) return q;
	}
	return nil;
}

- (BOOL)browser:(NSBrowser *)browser isLeafItem:(id)item
{
	IQStructureQuantum *q = (IQStructureQuantum *)item;
	if (![item isKindOfClass:[IQStructureQuantum class]])
		return NO;
	return [q.fields objectForKey:@"children"] == nil;
}

- (NSInteger)browser:(NSBrowser *)browser numberOfChildrenOfItem:(id)item
{
	IQStructureQuantum *q = (IQStructureQuantum *)item;
	IQArrayQuantum *children = (IQArrayQuantum *)[q quantumForKey:@"children"];
	return [children.quanta count];
}

- (id)browser:(NSBrowser *)browser child:(NSInteger)index ofItem:(id)item
{
	IQStructureQuantum *sq = (IQStructureQuantum *)item;
	IQArrayQuantum *children = (IQArrayQuantum *)[sq quantumForKey:@"children"];
	return [children.quanta objectAtIndex:index];
}

- (id)browser:(NSBrowser *)browser objectValueForItem:(id)item
{
	IQStructureQuantum *sq = (IQStructureQuantum *)item;
	return sq.name;
}

@end
