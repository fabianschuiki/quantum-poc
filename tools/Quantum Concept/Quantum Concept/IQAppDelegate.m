//
//  IQAppDelegate.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQAppDelegate.h"
#import "IQServer.h"
#import "IQServerInspector.h"
#import "IQQuantum.h"
#import "IQEditor.h"

@implementation IQAppDelegate

- (id)init
{
	self = [super init];
	if (self) {
		editorTypeMap = [[NSMutableDictionary dictionaryWithObjectsAndKeys:
						  @"IQStringEditor", @"string",
						  nil] retain];
		editors = [[NSMutableSet set] retain];
	}
	return self;
}

- (void)dealloc
{
    [super dealloc];
}

- (void)applicationDidFinishLaunching:(NSNotification *)aNotification
{
}

- (BOOL)canEditQuantum:(IQQuantum *)iq
{
	return [editorTypeMap objectForKey:iq.type] != nil;
}

- (void)editQuantum:(IQQuantum *)iq
{
	IQEditor *editor = nil;
	for (IQEditor *e in editors) {
		if (e.quantum == iq)
			editor = e;
	}
	
	if (!editor) {
		NSString *cls = [editorTypeMap objectForKey:iq.type];
		NSAssert(cls != nil, @"No editor available for %@", iq);
		editor = [NSClassFromString(cls) editorWithQuantum:iq];
		[editors addObject:editor];
	}
	
	[editor.window makeKeyAndOrderFront:nil];
}

- (void)editorClosed:(IQEditor *)editor
{
	[editors removeObject:editor];
}

@end
