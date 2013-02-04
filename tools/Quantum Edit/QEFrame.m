//
//  QEFrame.m
//  Quantum Edit
//
//  Created by Fabian Schuiki on 03.02.13.
//  Copyright 2013 Axamblis. All rights reserved.
//

#import "QEFrame.h"


struct FrameHeader {
	uint8_t type;
	uint32_t length;
} __attribute__((packed));


@implementation QEFrame

@synthesize type, data;


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

- (NSString *)description
{
	return [NSString stringWithFormat:@"%@(type %i, %i Bytes)", [super description], type, [data length]];
}

- (NSData *)serialize
{
	//Assemble the header.
	struct FrameHeader h;
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
	return [self unserialize:data consumed:consumed forgiving:NO];
}

+ (id)unserialize:(NSData *)data consumed:(NSUInteger *)consumed forgiving:(BOOL)forgiving
{
	if (consumed)
		*consumed = 0;
	
	//Read the frame header.
	struct FrameHeader h;
	if ([data length] < 5) {
		if (forgiving) return nil;
		[NSException raise:NSInvalidArgumentException format:@"Not enough data to unserialize frame from data with only %i Bytes, at least 5 Bytes required.", [data length]];
	}
	[data getBytes:&h length:5];
	
	//Read the payload data.
	if ([data length] < h.length + 5) {
		if (forgiving) return nil;
		[NSException raise:NSInvalidArgumentException format:@"Not enough data to unserialize frame; required %i Bytes, got only %i Bytes.", h.length + 5, [data length]];
	}
	QEFrame *f = [QEFrame frameWithType:h.type data:[data subdataWithRange:NSMakeRange(5, h.length)]];
	if (consumed)
		*consumed = h.length + 5;
	
	return f;
}

@end
