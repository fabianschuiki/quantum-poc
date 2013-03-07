Uncategorized
=============

Bandwidths
----------
Storing information quanta to different locations and converting quanta from one representation to another yields different execution times. E.g. saving a file to an FTP server is slower than to a local file. The system could keep track of bandwidths of certain conversion chains and storage locations. If the user is editing a file that is persisted at a really slow location, the system could automatically create a cache quantum somewhere where the bandwidth is higher (e.g. the local file system), and then push the information across the slow bandwidth at a lower frequency. This would also enable file managers to ask the operating system for a "preview of this quantum in less than 100ms". The operating system would keep track of the different rendering bandwidths and would guess whether it's worth opening the file to preview its contents, or not.

Progress Indicators
-------------------
Operations over a chain of converters that have a low bandwidth will yield high execution times. The system should spawn progress indication quanta that indicate how much of a operation has been executed. The GUIs could then display progress indicators for operations concerning them. E.g. sending an email message over SMTP will take a while, so the system would spawn a progress indicator that shows how much of the message has been sent.