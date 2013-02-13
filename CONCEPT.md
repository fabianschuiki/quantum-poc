Information Quantum Concept
===========================
This document describes general concepts and ideas for the Information Quantum system. This file will be moved to the proper IQ repository at a later stage when the proof of concept is finished.


General IQ Restrictions
-----------------------
- An IQ's ID may not change.
- An IQ's type may not change.
- An IQ cannot have multiple parents.


Client Requirements
-------------------
Clients should be able to:

- Modify portions of a raw IQ, i.e. replace a certain range of data of a raw IQ with some other data.
- Modify portions of a string IQ, dito.
- Create new IQs with their own IDs that are mapped to global IDs through the server.


ID Mapping
----------
Clients may use a certain range of IDs at their will. When communicating, the server establishes a translation map between client and global IDs. It then communicates the ID equalities to the client which may alter its own IDs to match the global ones. The client then transmits the ID equality back to the server to indicate the ID is no longer in use. The server may then remove the ID from the map.


Interfaces
----------
The server should define interfaces that describe a certain functionality. An IQ automatically implements the interface if it has the given subquanta.


Messages
--------
This section lists all messages the server and client might want to exchange. All messages contain the `type` field specifying the message type, and the `rid` field specifying the request type. Response to a request are required to carry the original request ID, which is an arbitrary number to be set by the requester.


### GET

- `path` *optional*: Path to the requested information quantum.
- `id` *optional*: ID of the requested information quantum.
- `as` *optional*: Type the receiver is able to edit. The server will try to cast the given quantum to this type.

Requests the information quantum at the given path or with the given ID. The server will respond with a **SET** message containing the quantum, or an error if the quantum doesn't exist.


### SET

- `id`: ID of the contained information quantum.
- `iq`: JSON representation of the information quantum.

Transports an entire information quantum. If the given ID already exists, the receiver should check whether the type of the IQ matches and report an error if it doesn't. Usually, the initial transfer of an IQ occurs through a **SET** message.


### DONE
Returned by the receiver of a **SET {...}** message. Obsolete, should be removed soon.


### GET STRING

- `id`: ID of the string information quantum.
- `range` *optional*: Range of the requested substring.

Requests the given range of a string IQ, or the entire string if `range` is omitted.


### SET STRING

- `id`: ID of the string information quantum to be altered.
- `range` *optional*: Range of the string to be replaced.
- `string`: Replacement string.

For the given string IQ, replaces the substring given by `range` (or the entire string if `range` is omitted) with the given replacement `string`. After a string IQ has been transmitted through a **SET** message, it should only be altered through this message. This will enable partial updates of other dependent information quanta.


### GET DATA

- `id`: ID of the raw information quantum.
- `range` *optional*: Range of the requested subdata.

Requests the given range of a raw IQ, or the entire data if `range` is omitted.


### SET DATA

- `id`: ID of the raw information quantum to be altered.
- `range` *optional*: Range of the data to be replaced.
- `data`: Replacement data.

For the given raw IQ, replaces the subdata given by `range` (or the entire data if `range` is omitted) with the given replacement `data` block. After a raw IQ has been transmitted through a **SET** message, it should only be altered through this message. This will enable partial updates of other dependent information.