//
//  IQStringQuantum.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQQuantum.h"

@interface IQStringQuantum : IQQuantum

@property (readonly) NSMutableString *string;

- (id)init;

@end
