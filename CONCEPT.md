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