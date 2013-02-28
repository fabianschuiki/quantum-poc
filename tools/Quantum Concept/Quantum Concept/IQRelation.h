//
//  IQRelation.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import <Foundation/Foundation.h>

@class IQQuantum;
@class IQCaster;

@interface IQRelation : NSObject

@property (retain) IQQuantum *parent;
@property (retain) IQQuantum *wrapper;
@property (retain) IQCaster *caster;

+ (id)relation;

@end
