//
//  QEFrame.h
//  Quantum Edit
//
//  Created by Fabian Schuiki on 03.02.13.
//  Copyright 2013 Axamblis. All rights reserved.
//

#import <Cocoa/Cocoa.h>


@interface QEFrame : NSObject
{
	NSUInteger type;
	NSData *data;
}

@property (readonly) NSUInteger type;
@property (readonly) NSData *data;

+ (id)unserialize:(NSData *)data;
+ (id)unserialize:(NSData *)data consumed:(NSUInteger *)consumed;
+ (id)unserialize:(NSData *)data consumed:(NSUInteger *)consumed forgiving:(BOOL)forgiving;

+ (id)frameWithType:(NSUInteger)type data:(NSData *)data;
- (id)initWithType:(NSUInteger)type data:(NSData *)data;

- (NSData *)serialize;

@end
