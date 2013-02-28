//
//  IQServer.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import <Foundation/Foundation.h>

@class IQRepository;


@interface IQServer : NSObject

@property (readonly) IQRepository *repository;

- (id)init;

@end
