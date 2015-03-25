.. note:: **WARNING**: This project isn't actively maintained anymore, mainly due to
    the lack of time. I migrated it to github by request. Hopefully somebody
    might find this useful as a base to work on again. Eventhough it should
    still be compatible with current PHP versions. It does not support *newer*
    features like namespace and such. Of course Pull-Requests are always
    welcome. If somebody wants to take over maintership completely I would be
    happy to discuss that.

=====
phUML
=====

What is this all about?
=======================

phUML is fully automatic UML_ class diagramm generator written PHP_. It is
capable of parsing any PHP5_ object oriented source code and create an
appropriate image representation of the oo structure based on the UML_
specification.

.. _UML: http://en.wikipedia.org/wiki/Unified_Modeling_Language
.. _PHP: http://php.net
.. _PHP5: http://www.php.net/downloads.php#v5


What does it look like?
=======================

.. image:: https://raw.githubusercontent.com/jakobwesthoff/phuml/master/images/phuml_example_thumbnail.jpg
   :alt: Class diagram of the phUML generator
   :align: center
   :target: https://raw.githubusercontent.com/jakobwesthoff/phuml/master/images/phuml_example.png

The image shown here is the class diagramm which phUML created when run on
its own codebase. This image is hardly readable, because it has been resized
to fit in the layout of this page. You can take a look at the complete image
by clicking here_

.. _here: https://raw.githubusercontent.com/jakobwesthoff/phuml/master/images/phuml_example.png


Can I use this for my own projects?
===================================

phUML should be compatible with any object oriented code written in PHP5_. At
the moment it unfortunatly does not support any PHP4_ code. 

.. _PHP4:  http://php.net

phUML has quite a informative help interface, which can be accessed by calling
it with the -h option. ::
	
	$ phuml -h

The phUML generator works with so called processors, which may be used in a
chain to create a lot of different output formats. Every available processor
can be listed by calling phUML with the -l option. ::

	$ phuml -l

The most important processor used to create images of any kind is the
graphviz processor. As its name indicates it outputs information in the so
called dot language used by graphviz_. To sucessfully handle this output
format and create the desired images you will need the graphviz_ toolkit
installed on your system. You may then call the neato or dot
executables, which are part of graphviz_, to process the created file
manually or you may phUML do this for you by using the dot or neato
processor.

.. _graphviz: http://www.graphviz.org

You should just play around with the phUML commandline tool to get a better
understanding of what the processors do and how they work. To give you a short
example of how a complete phUML call could look like, this is the one used
generate the example you can see above. ::

	$ phuml -r ./ -dot -createAssociations false -neato example.png
