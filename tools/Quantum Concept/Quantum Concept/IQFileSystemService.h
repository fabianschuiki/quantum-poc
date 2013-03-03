//
//  IQFileSystemService.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 03.03.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import <Foundation/Foundation.h>

@class IQServer;

@interface IQFileSystemService : NSObject

@property (readonly) IQServer *server;

- (id)initWithServer:(IQServer *)s;

@end
