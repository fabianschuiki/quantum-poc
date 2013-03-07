//
//  IQDataQuantum.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQQuantum.h"

@interface IQDataQuantum : IQQuantum

@property (readonly) NSMutableData *data;

- (id)init;

@end
