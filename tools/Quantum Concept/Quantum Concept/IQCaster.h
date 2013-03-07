//
//  IQCaster.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import <Foundation/Foundation.h>

@class IQQuantum;


@interface IQCaster : NSObject

@property (readonly) NSString *input;
@property (readonly) NSString *output;

+ (id)caster;

- (void)cast:(IQQuantum *)from to:(IQQuantum *)to;
- (void)forwardCast:(id)input to:(id)output;
- (void)backwardCast:(id)output to:(id)input;

@end
