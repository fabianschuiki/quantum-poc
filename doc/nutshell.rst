Information Quantum in a Nutshell
=================================

The Information Quantum project aims to increase the abstraction of information in modern computing systems. Like files introduce an abstraction of the underlying storage mechanism, information quanta introduce an abstraction of information organization. Modern operating systems like Mac OS, Linux and Windows generate their user experience through programs that open, edit and save files. Many different file formats represent the same type of information, e.g. *.jpg*, *.png*, *.gif* and *.tiff* files all contain an image. The user's image viewer must be able to read all these formats in order to show a picture. Thus a program developer spends a significant amount of time enabling his software to properly make sense of the different information formats, instead of focusing on his main task: developing the program's main functionality.

The system described in this document provides a new approach to how information is stored and edited. The task of organizing and interpreting information is moved into a separate layer of abstraction, called the *Quantum Server*. Programs are no longer concerned with reading files and making sense of their contents, but rather modifying information itself.

.. image:: ApproachComparison.pdf


System Components
=================

The Information Quantum System may be divided into several components which are detailed in the following sections.

Information Sharing
-------------------
Information quanta need to be shared and synchronized among multiple processes. This is necessary since a program obtains a quantum from the system and modifies it, after which the system itself must be able to see the changes and execute any required actions. Since the content of an information quanta may change due to a number of external factors, one requirement for the system is that Information Quanta can be edited simultaneously by multiple processes.

.. note::
	Information Quanta can be edited simultaneously by multiple processes.

Managing the sharing of information may be achieved through different techniques, all of which the Quantum Server might implement:

Shared Memory
~~~~~~~~~~~~~
Entire quanta may be made available to other processes through the use of shared memory segments where the operating system supports it. Upon request, the Quantum Server would make a certain block of memory containing the information accessible to the requesting process. Special security measures must be taken in order to ensure that access to the information is synchronized.

.. note::
	This approach uses the least amount of memory, since shared information quanta do not occupy additional memory.

.. important::
	One of the major drawbacks of this approach is that shared memory appears to be a deprecated matter. Linux and the like seem to be moving to memory mapped files. This might be an alternative. Skimming through shared memory documentation, it appears that most systems do not allow arbitrarily large memory segments to be shared, which is a grave limitation.

Sockets
~~~~~~~
Quanta may be transported and kept in sync over a socket connection. Each process would keep a copy of the information it uses in its own memory. Messages to/from the server would keep the copies in sync.

.. important::
	This approach possibly consumes a lot of memory since each process needs to keep its own copy of the information. In a simple setup where one program uses an information quantum provided by a service, three copies of the quantum would reside in memory: one in the server, one in the program and one in the service that manages it.

	Another drawback is the speed limit since all quanta need to be serialized and sent over a socket connection. The performance of the IQS in this case highly depends on the performance of the operating system's socket/pipe implementations.

Information Conversion
----------------------
The true power and potential of this system arises through small programs and scripts that convert an information quantum from one type to another.

One of the most fundamental converters is one that takes a *file* IQ and converts it to a *raw data* IQ simply by reading the file's contents. Another converter might be able to convert *raw data* quanta into *image* quanta by interpreting the data as a JPEG image. If the user asks an image editor to edit a file, the operating system would chain together these two converters to turn the file into raw data and eventually into an image the editor may modify. At the end of the day, the converters would do the conversion backwards, turning the modified image into JPEG data and eventually into a file on disk.

To make the system as powerful as possible, even the smallest PHP or Ruby script should be able to act as a converter. This enables anybody to teach the system to interpret data the way they like. If the system has an HTML reader like a web browser, a user might write a script that converts Markdown files into HTML, thus enabling the web browser to natively read Markdown files.

.. note::
	As a general picture, with this system the user should be able to use an image editor like Gimp to edit an image found on one particular page of a PDF document which is inside a ZIP archive on a remote FTP server.

Sources/Sinks
-------------
Aforementioned components are concerned with transporting and modifying existing data. One important component of the system would be one that creates information quanta and is also able to consume it. You could think of a Twitter service as an information source as it spawns tweet quanta on your system. An SMTP server would be an information sink as it consumes an information quantum, i.e. it moves the information outside of the framework the IQS.

Storage
-------
Not only need quanta to be created or consumed, they also need to be stored somewhere. If you write a document you want it to be present on your computer the next time you restart it. If you compose a new email message and store it on your IMAP server, you expect it to be there when switching devices.

.. important::
	The system needs to provide an answer for the question: "Where is information being stored?"

A *Filesystem* service might spawn a root information quantum that represents the root of a classical UNIX file system. Files and directories are represented as chidlren of the root quantum. New information added to any of its children would cause the service to persist the information to disk.

An *IMAP* service might spawn mailbox quanta for each of your configured mail accounts. If you add a message to one of them it automatically gets persisted to the server.

.. important::
	Services providing storage for an IQ must be able to communicate this functionality to the server.

.. caution::
	If an information quantum is added to a parent quantum that is not backed by some form of storage, this information gets lost when the system reboots. Further investigation is needed whether this might happen at all or whether all quanta need to be backed by storage anyway.

Further Abstraction
~~~~~~~~~~~~~~~~~~~
The beginning of this section detailed how storage might work, yet this mechanisms are visible to the end user. Furthermore, if a program wants to persist an image IQ, the user would still have to organize these things themself.

The operating system, or rather the desktop environment, might want to provide default location for storing certain types of information. For example, it might provide a container for images, music, documents, but also for program configuration files or email accounts. This further distances the program from having to decide where to persist information. Rather than saying *store this data in ".myapp/config"* it might actually tell the operating system to *store this configuration data and name it "config"*. The system (maybe even in cooperation with the user) would then choose the appropriate storage location.

.. note::
	This might even be taken a step further by giving programs the chance to ask for certain configuration environments, e.g. *store this netowrk configuration data on this machine*, but *store this account information for this user*.

Decoupling programs from choosing physical storage media simplifies tasks such as moving user data to the Cloud. An operating system might give the user the option to *"Store E-Mail Accounts in the Cloud"*. If the user chooses this option, the OS would simply return a different information container for *email account data container* requests: It might either be in the user's configuration on disk, or in the user's configuration on a network volume.


Standardizing Information
=========================
Most fundamental information quanta may be standardized. A quantum containing raw byte data would probably contain the data and various attributes, such as the data's size. A quantum containing a bitmap image would probably have a data block containing the pixel data, and fields for the bitmap width, height and the format of the pixel data.

Slowly and carefully standardizing certain parts of a quantum would allow for high compatibility between modules. Libraries for various programming languages may be written that deal with images, audio, video, text and other common formats. Even certain parts of an application's configuration may be standardized, like a user's browser bookmarks. This would enable the user to use different browsers and still have the same basic bookmarks. Each browser would have the freedom to attach its own custom fields, which may be standardized further down the road if they prove popular.

Adhering to standard information formats should be made as easy as possible such as to encourage developers to actually use these standards. Take the Go programming language as an example here: Simply implementing the right methods means you're implementing an interface. Applied to the IQS: Simply choosing the standard names for your IQ's fields means you're conforming to the standard. Additional information? Just put them in additional fields.