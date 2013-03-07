//
//  IQArrayQuantum.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 03.03.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQQuantum.h"

@interface IQArrayQuantum : IQQuantum
{
	NSMutableArray *quanta;
}

@property (copy) NSString *quantaType;
@property (readonly) NSArray *quanta;

- (void)addQuantum:(IQQuantum *)q;
- (void)removeQuantum:(IQQuantum *)q;

@end
