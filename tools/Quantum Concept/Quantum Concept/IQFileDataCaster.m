//
//  IQFileDataCaster.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQFileDataCaster.h"
#import "IQDataQuantum.h"

@implementation IQFileDataCaster

- (NSString *)input  { return @"file"; }
- (NSString *)output { return @"data"; }

- (void)forwardCast:(id)input to:(id)output
{
	NSLog(@"reading file %@", input);
	[[(IQDataQuantum *)output data] setData:[NSData dataWithContentsOfFile:@"/tmp/iq"]];
}

- (void)backwardCast:(id)output to:(id)input
{
	NSLog(@"writing file %@", input);
	[[output data] writeToFile:@"/tmp/iq" atomically:NO];
}

@end
