//
//  QEEditor.m
//  Quantum Edit
//
//  Created by Fabian Schuiki on 04.02.13.
//
//

#import "QEEditor.h"
#import "QEApplication.h"

@implementation QEEditor
@synthesize path;

- (id)initWithPath:(NSString *)p
{
	self = [self init];
	if (self) {
		path = [p retain];
		
		//Request the information quantum.
		requestID = [NSApp requestQuantumWithPath:path];
		NSLog(@"%@ waiting for request %i to complete", self, requestID);
	}
	return self;
}

- (void)dealloc
{
	[path release];
	[super dealloc];
}

@end
