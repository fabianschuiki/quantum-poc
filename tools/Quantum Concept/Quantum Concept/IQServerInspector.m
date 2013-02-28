//
//  IQServerInspector.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQServerInspector.h"
#import "IQServer.h"
#import "IQRepository.h"
#import "IQQuantum.h"
#import "IQStructureQuantum.h"
#import "IQDataQuantum.h"
#import "IQStringQuantum.h"
#import "IQCaster.h"
#import "IQAppDelegate.h"

@implementation IQServerInspector

@synthesize outlineView, server;


- (id)init
{
	self = [super init];
	if (self) {
		[[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(quantumChanged:) name:@"IQQuantumChanged" object:nil];
	}
	return self;
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
		return [server.repository.quanta objectAtIndex:index];
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
		return [server.repository.quanta count];
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
		} else if ([iq isKindOfClass:[IQDataQuantum class]]) {
			return [NSString stringWithFormat:@"%lu Bytes", [((IQDataQuantum *)iq).data length]];
		} else if ([iq isKindOfClass:[IQStringQuantum class]]) {
			NSString *rawString = ((IQStringQuantum *)iq).string;
			NSString *str = rawString;
			if ([str length] > 1000) {
				str = [str stringByReplacingCharactersInRange:NSMakeRange(1000, [str length]-1000) withString:@"…"];
			}
			return [NSString stringWithFormat:@"\"%@\"", str];
		}
		return [item description];
	}
	return nil;
}

- (IBAction)castQuantum:(id)sender
{
	// Make a list of available casts.
	IQQuantum *iq = [self.outlineView itemAtRow:self.outlineView.selectedRow];
	NSMenu *menu = self.castPopup.menu;
	[menu removeAllItems];
	for (IQCaster *caster in server.casters) {
		if ([iq.type isEqualToString:caster.input] || [iq.type isEqualToString:caster.output]) {
			NSString *title = [NSString stringWithFormat:@"%@ ↔ %@", caster.input, caster.output];
			NSMenuItem *item = [menu addItemWithTitle:title action:NULL keyEquivalent:@""];
			[item setRepresentedObject:caster];
		}
	}
	
	[NSApp beginSheet:self.castPicker modalForWindow:self.outlineView.window modalDelegate:nil didEndSelector:NULL contextInfo:nil];
	[self.castPicker makeKeyAndOrderFront:nil];
}

- (IBAction)cancelCast:(id)sender
{
	[NSApp endSheet:self.castPicker];
	[self.castPicker close];
}

- (IBAction)acceptCast:(id)sender
{
	IQCaster *caster = self.castPopup.selectedItem.representedObject;
	IQQuantum *iq = [self.outlineView itemAtRow:self.outlineView.selectedRow];
	
	[NSApp endSheet:self.castPicker];
	[self.castPicker close];
	
	[self.server castQuantum:iq with:caster];
}

- (IBAction)editQuantum:(id)sender
{
	IQQuantum *iq = [self.outlineView itemAtRow:self.outlineView.selectedRow];
	[[NSApp delegate] editQuantum:iq];
}

- (BOOL)validateMenuItem:(NSMenuItem *)menuItem
{
	BOOL anythingSelected = [outlineView numberOfSelectedRows] > 0;
	
	if ([menuItem action] == @selector(castQuantum:)) {
		return anythingSelected;
	}
	if ([menuItem action] == @selector(editQuantum:)) {
		if (anythingSelected) {
			IQQuantum *iq = [self.outlineView itemAtRow:self.outlineView.selectedRow];
			return [[NSApp delegate] canEditQuantum:iq];
		} else {
			return NO;
		}
	}
	return NO;
}

@end
