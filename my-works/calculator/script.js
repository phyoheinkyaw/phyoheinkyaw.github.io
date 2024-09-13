const display = document.getElementById("display");
const buttons = document.querySelectorAll(".btn");
let currentInput = "";
let operator = null;
let firstOperand = null;
let shouldResetDisplay = false;

// Add event listeners for mouse clicks on buttons
buttons.forEach(button => {
  button.addEventListener("click", () => {
    const key = button.getAttribute("data-key");

    if (button.id === "clear") {
      clearCalculator();
    } else if (button.id === "equals") {
      calculateResult();
    } else if (["+", "-", "*", "/"].includes(key)) {
      handleOperator(key);
    } else {
      handleNumber(key);
    }
  });
});

// Add event listener for keyboard input
document.addEventListener("keydown", (event) => {
  const key = event.key;

  if (key === "Escape") {
    clearCalculator(); // 'Escape' clears the calculator
  } else if (key === "Enter" || key === "=") {
    calculateResult(); // 'Enter' and '=' keys calculate the result
  } else if (["+", "-", "*", "/"].includes(key)) {
    handleOperator(key); // Operators are handled normally
  } else if (!isNaN(key) || key === ".") {
    handleNumber(key); // Numeric keys and '.' for decimals
  }
});

function clearCalculator() {
  currentInput = "";
  operator = null;
  firstOperand = null;
  shouldResetDisplay = false;
  display.value = "";
}

function handleNumber(number) {
  if (shouldResetDisplay) {
    currentInput = "";
    shouldResetDisplay = false;
  }

  // Avoid adding multiple dots
  if (number === "." && currentInput.includes(".")) return;

  currentInput += number;
  display.value = currentInput;
}

function handleOperator(op) {
  if (firstOperand === null) {
    firstOperand = parseFloat(currentInput);
  } else if (operator) {
    firstOperand = operate(operator, firstOperand, parseFloat(currentInput));
  }

  operator = op;
  shouldResetDisplay = true;
  display.value = `${firstOperand} ${operator}`;
}

function calculateResult() {
  if (operator && firstOperand !== null) {
    const secondOperand = parseFloat(currentInput);
    display.value = `${firstOperand} ${operator} ${secondOperand} = ${operate(operator, firstOperand, secondOperand)}`;
    currentInput = "";
    firstOperand = null;
    operator = null;
  }
}

function operate(operator, a, b) {
  switch (operator) {
    case "+":
      return a + b;
    case "-":
      return a - b;
    case "*":
      return a * b;
    case "/":
      return b === 0 ? "Error" : a / b;
    default:
      return b;
  }
}
