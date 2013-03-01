//
//  IQStructureQuantum.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQQuantum.h"

@interface IQStructureQuantum : IQQuantum
{
	NSMutableDictionary *fields;
}

@property (readonly) NSDictionary *fields;

- (id)init;

- (void)setQuantum:(IQQuantum *)quantum forKey:(NSString *)key;
- (void)setProxyWithDelegate:(id)delegate forKey:(NSString *)key;
- (IQQuantum *)quantumForKey:(NSString *)key;

@end
