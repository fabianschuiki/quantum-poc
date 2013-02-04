//
//  QEEditor.h
//  Quantum Edit
//
//  Created by Fabian Schuiki on 04.02.13.
//
//

#import <Foundation/Foundation.h>

@interface QEEditor : NSObject
{
	IBOutlet NSWindow *window;
	IBOutlet NSTextView *textView;
	NSString *path;
	NSInteger requestID;
}

@property (readonly) NSString *path;

- (id)initWithPath:(NSString *)path;

@end
