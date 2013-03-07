//
//  IQEditor.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQEditor.h"

@implementation IQEditor

+ (id)editorWithQuantum:(IQQuantum *)q
{
	return [[[self alloc] initWithQuantum:q] autorelease];
}

- (id)initWithQuantum:(IQQuantum *)q
{
	self = [self init];
	if (self) {
		self.quantum = q;
	}
	return self;
}

@end
