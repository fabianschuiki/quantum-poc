//
//  IQStringEditor.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQEditor.h"

@interface IQStringEditor : IQEditor

@property (assign) IBOutlet NSTextView *textView;

- (void)updateTextViewContents;

- (IBAction)save:(id)sender;

@end
