//
//  IQStructureQuantum.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQStructureQuantum.h"
#import "IQProxyQuantum.h"

@implementation IQStructureQuantum

- (id)init
{
	self = [super init];
	if (self) {
		fields = [[NSMutableDictionary dictionary] retain];
	}
	return self;
}

- (void)dealloc
{
	[fields release];
	return [super dealloc];
}

- (NSDictionary *)fields
{
	return fields;
}

- (void)setQuantum:(IQQuantum *)quantum forKey:(NSString *)key
{
	[fields setObject:quantum forKey:key];
	quantum.name = key;
}

- (void)setProxyWithDelegate:(id)delegate forKey:(NSString *)key
{
	IQProxyQuantum *pq = [IQProxyQuantum quantum];
	pq.delegate = delegate;
	[self setQuantum:pq forKey:key];
}

- (IQQuantum *)quantumForKey:(NSString *)key
{
	IQQuantum *iq = [fields objectForKey:key];
	if ([iq isKindOfClass:[IQProxyQuantum class]]) {
		iq = [((IQProxyQuantum *)iq).delegate resolveProxy:key ofQuantum:self];
		[fields setObject:iq forKey:key];
		iq.name = key;
	}
	return iq;
}

@end
