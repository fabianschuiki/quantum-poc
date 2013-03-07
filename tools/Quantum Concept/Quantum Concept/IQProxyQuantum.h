//
//  IQProxyQuantum.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 01.03.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQQuantum.h"

@class IQStructureQuantum;

@protocol IQProxyQuantumDelegate <NSObject>
- (IQQuantum *)resolveProxy:(NSString *)key ofQuantum:(IQStructureQuantum *)parent;
@end

@interface IQProxyQuantum : IQQuantum

@property (assign) id<IQProxyQuantumDelegate> delegate;

@end
