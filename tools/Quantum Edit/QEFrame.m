//
//  QEFrame.m
//  Quantum Edit
//
//  Created by Fabian Schuiki on 03.02.13.
//  Copyright 2013 Axamblis. All rights reserved.
//

#import "QEFrame.h"


@implementation QEFrame

+ (id)frameWithType:(NSUInteger)type data:(NSData *)data
{
	return [[[self alloc] initWithType:type data:data] autorelease];
}

- (id)initWithType:(NSUInteger)t data:(NSData *)d
{
	self = [super init];
	if (self) {
		type = t;
		data = [d retain];
	}
	return self;
}

- (void)dealloc
{
	[data release];
	[super dealloc];
}

- (NSUInteger)type
{
	return type;
}

- (NSData *)data
{
	return data;
}

- (NSData *)serialize
{
	//Assemble the header.
	struct {
		uint8_t type;
		uint32_t length;
	} __attribute__((packed)) h;
	h.type = type;
	h.length = [data length];
	
	//Compose the serialized data.
	NSMutableData *r = [NSMutableData dataWithBytes:&h length:5];
	[r appendData:data];
	return r;
}

+ (id)unserialize:(NSData *)data
{
	return [self unserialize:data consumed:NULL];
}

+ (id)unserialize:(NSData *)data consumed:(NSUInteger *)consumed
{
	return nil;
}

@end
