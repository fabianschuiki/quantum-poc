//
//  IQStringEditor.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQStringEditor.h"
#import "IQAppDelegate.h"
#import "IQStringQuantum.h"

@implementation IQStringEditor

- (id)initWithQuantum:(IQQuantum *)q
{
	self = [super initWithQuantum:q];
	if (self) {
		[NSBundle loadNibNamed:@"StringEditor" owner:self];
		[self updateTextViewContents];
	}
	return self;
}

- (void)windowWillClose:(NSNotification *)notification
{
	[[NSApp delegate] editorClosed:self];
}

- (void)updateTextViewContents
{
	// Setup the display attributes for the string.
	NSDictionary *attrs = [NSDictionary dictionaryWithObjectsAndKeys:[NSFont userFixedPitchFontOfSize:12], NSFontAttributeName, nil];
	
	// Prepare the attributed text for display.
	NSString *str = [(IQStringQuantum *)self.quantum string];
	NSAttributedString *astr = [[[NSAttributedString alloc] initWithString:str attributes:attrs] autorelease];
	
	// Move the string to the text view.
	[self.textView.textStorage setAttributedString:astr];
}

- (void)textDidChange:(NSNotification *)notification
{
	// Set the window's edit state based on whether the text view's text and the quantum's string differ.
	NSString *stringQuantum = [(IQStringQuantum *)self.quantum string];
	NSString *stringEditor = self.textView.textStorage.string;
	
	// Compare.
	BOOL altered = ([stringQuantum length] > 100000 || [stringEditor length] > 100000 || ![stringQuantum isEqualToString:stringEditor]);
	[self.window setDocumentEdited:altered];
}

- (IBAction)save:(id)sender
{
	[[(IQStringQuantum *)self.quantum string] setString:self.textView.textStorage.string];
	[self.quantum commit];
	[self.window setDocumentEdited:NO];
}

@end
