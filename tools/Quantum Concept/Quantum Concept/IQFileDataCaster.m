//
//  IQFileDataCaster.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQFileDataCaster.h"
#import "IQDataQuantum.h"
#import "IQStringQuantum.h"
#import "IQStructureQuantum.h"

@implementation IQFileDataCaster

- (NSString *)input  { return @"file"; }
- (NSString *)output { return @"data"; }

- (void)forwardCast:(id)input to:(id)output
{
	NSLog(@"reading file %@", input);
	NSString *path = [(IQStringQuantum *)[(IQStructureQuantum *)input quantumForKey:@"path"] string];
	[[(IQDataQuantum *)output data] setData:[NSData dataWithContentsOfFile:path]];
}

- (void)backwardCast:(id)output to:(id)input
{
	NSLog(@"writing file %@", input);
	NSString *path = [(IQStringQuantum *)[(IQStructureQuantum *)input quantumForKey:@"path"] string];
	[[output data] writeToFile:path atomically:NO];
}

@end
