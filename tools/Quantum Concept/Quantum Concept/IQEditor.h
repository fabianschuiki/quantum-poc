//
//  IQEditor.h
//  Quantum Concept
//
//  Created by Fabian Schuiki on 28.02.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import <Foundation/Foundation.h>

@class IQQuantum;


@interface IQEditor : NSObject

@property (assign) IBOutlet NSWindow *window;
@property (retain) IQQuantum *quantum;

+ (id)editorWithQuantum:(IQQuantum *)q;
- (id)initWithQuantum:(IQQuantum *)q;

@end
