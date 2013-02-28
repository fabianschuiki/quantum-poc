//
//  IQQuantum.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface IQQuantum : NSObject

@property (assign) NSUInteger ID;
@property (copy) NSString *type;
@property (copy) NSString *name;

- (id)init;

@end
