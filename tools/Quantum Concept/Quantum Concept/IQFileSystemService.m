//
//  IQFileSystemService.m
//  Quantum Concept
//
//  Created by Fabian Schuiki on 03.03.13.
//  Copyright (c) 2013 Fabian Schuiki. All rights reserved.
//

#import "IQFileSystemService.h"
#import "IQServer.h"
#import "IQStructureQuantum.h"
#import "IQArrayQuantum.h"
#import "IQProxyQuantum.h"
#import "IQStringQuantum.h"

@implementation IQFileSystemService

@synthesize server;

- (id)initWithServer:(IQServer *)s
{
	self = [self init];
	if (self) {
		server = s;
		
		// Create the root information quantum.
		IQStructureQuantum *root = [IQStructureQuantum quantum];
		root.type = @"directory";
		root.name = @"filesystem";
		[root setProxyWithDelegate:self forKey:@"children"];
		[server.repository addQuantum:root];
		
		IQStringQuantum *path = [IQStringQuantum quantum];
		[path.string setString:@"/"];
		[server.repository addQuantum:path];
		[root setQuantum:path forKey:@"path"];
	}
	return self;
}

- (IQQuantum *)resolveProxy:(NSString *)key ofQuantum:(IQStructureQuantum *)parent
{
	if (![key isEqualToString:@"children"]) return nil;
	NSString *path = [(IQStringQuantum *)[parent quantumForKey:@"path"] string];
	NSLog(@"FileSystem: Loading contents of %@", path);
	
	IQArrayQuantum *children = [IQArrayQuantum quantum];
	children.quantaType = @"file|directory";
	[server.repository addQuantum:children];
	
	// Fill in the children.
	NSFileManager *fm = [NSFileManager defaultManager];
	NSArray *names = [fm contentsOfDirectoryAtPath:path error:NULL];
	for (NSString *name in names) {
		NSString *subpath = [path stringByAppendingPathComponent:name];
		NSDictionary *attrs = [fm attributesOfItemAtPath:subpath error:NULL];
		BOOL isDirectory = [[attrs objectForKey:NSFileType] isEqualToString:NSFileTypeDirectory];
		
		IQStructureQuantum *q = [IQStructureQuantum quantum];
		q.name = name;
		[server.repository addQuantum:q];
		[children addQuantum:q];
		
		IQStringQuantum *pq = [IQStringQuantum quantum];
		[pq.string setString:subpath];
		[server.repository addQuantum:pq];
		[q setQuantum:pq forKey:@"path"];
		
		if (isDirectory) {
			q.type = @"directory";
			[q setProxyWithDelegate:self forKey:@"children"];
		} else {
			q.type = @"file";
		}
	}
	
	return children;
}

@end
