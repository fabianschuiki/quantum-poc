//
//  IQDataStringCaster.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQDataStringCaster.h"
#import "IQStringQuantum.h"
#import "IQDataQuantum.h"

@implementation IQDataStringCaster

- (NSString *)input  { return @"data"; }
- (NSString *)output { return @"string"; }

- (void)forwardCast:(id)input to:(id)output
{
	NSMutableString *string = [(IQStringQuantum *)output string];
	NSData *data = [(IQDataQuantum *)input data];
	[string setString:[[[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding] autorelease]];
}

- (void)backwardCast:(IQQuantum *)output to:(IQQuantum *)input
{
	NSString *string = [(IQStringQuantum *)output string];
	NSMutableData *data = [(IQDataQuantum *)input data];
	[data setData:[string dataUsingEncoding:NSUTF8StringEncoding]];
}

@end
