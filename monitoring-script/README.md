# Monitor script

PHP script designed for specific monitoring, reporting tasks, and sending emails related to two different stores.

## Usage

Run the script using the following command-line format:

`php main.php <taskName> <storeName>`

## Parameters

- `<taskName>`: Task name (daily_report for Daily Report, check for Inventory Monitor [to check for sales count and amount each predefined time ex. 20 or 30 mins]).
- `<storeName>`: Store name (alomgyar for Alomgyar, olcsokonyvek for Olcsokonyvek) -- not required in case we are using daily_report command.

## Example

`php main.php check alomgyar`<br>
`php main.php check olcsokonyvek`<br>
`php main.php daily_report`
