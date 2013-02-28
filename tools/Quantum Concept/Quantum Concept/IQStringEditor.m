//
//  IQStringEditor.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQStringEditor.h"
#import "IQAppDelegate.h"

@implementation IQStringEditor

- (id)init
{
	self = [super init];
	if (self) {
		[NSBundle loadNibNamed:@"StringEditor" owner:self];
	}
	return self;
}

- (void)windowWillClose:(NSNotification *)notification
{
	[[NSApp delegate] editorClosed:self];
}

@end
